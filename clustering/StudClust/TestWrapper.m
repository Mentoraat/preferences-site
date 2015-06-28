
nStudents = 50;
nClust = 5;
nInClust = nStudents/nClust; %Should be round number
ClusterStrength = 0.8;
MaxScore = 5;
nRoles = 10;

%Make some testdata
if 1
    Dpref = zeros(nStudents);
    
    for i = 1:nStudents
        %Determine real cluster membership
        RealClust = floor((i-1)/nInClust)+1;
        
        %Draw inside own cluster
        withinclustindices = (RealClust-1)*nInClust+1:RealClust*nInClust;
        withinclustindices(withinclustindices==i) = []; %Remove self
        Iinside = randsample(withinclustindices,MaxScore);
        
        %Draw outside own cluster
        allindices = 1:nStudents;
        allindices = setdiff(allindices,withinclustindices);
        allindices(allindices==i) = [];
        Ioutside = randsample(allindices,MaxScore);  
        
        I = [Iinside; Ioutside];
        selector = double(rand(1,MaxScore)>ClusterStrength)+1;
        
        Dpref(i,I(sub2ind(size(I),selector,1:MaxScore))) = 1:MaxScore;
    end
    
    Dbelbin = zeros(nStudents,nRoles);
    for i = 1:nStudents
        Dbelbin(i,randperm(nRoles,2)) = 1:2;
    end
    save testdata Dpref Dbelbin
else
    load testdata
end

GoodClust = repmat(1:5,10,1);
GoodClust = GoodClust(:);
[a,b,c] = ClustStudFit(GoodClust,Dpref,Dbelbin)

imagesc(Dpref);

%
% %Random clustering
% BadClust = GoodClust(randperm(50));
% [a,b,c] = ClustStudFit(BadClust,Dpref,Dbelbin)
%
% %Skewed team roles
% DbelbinBad = Dbelbin;
% DbelbinBad(1:2:end,:) = sort(DbelbinBad(1:2:end,:),2);
% [a,b,c] = ClustStudFit(BadClust,Dpref,DbelbinBad)
%

